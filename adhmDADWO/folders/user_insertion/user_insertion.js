$(document).ready(function () {
	// var table_id 	= "user_insertion_datatable";
	init_datatable(table_id, form_name, action);
});

var company_name = sessionStorage.getItem("company_name");
var company_address = sessionStorage.getItem("company_name");
var company_phone = sessionStorage.getItem("company_name");
var company_email = sessionStorage.getItem("company_name");
var company_logo = sessionStorage.getItem("company_name");

var form_name = 'User Insertion';
var form_header = '';
var form_footer = '';
var table_name = '';
var table_id = 'user_insertion_datatable';
var action = "datatable";


function showLoader() {
	$("#loader").css("display", "inline-block"); // or "block" depending on your preference
}

function hideLoader() {
	$("#loader").css("display", "none");
}

// $(document).ready(function () {
//     var table = $('#user_insertion_datatable').DataTable({
//         "processing": true,
//         "serverSide": false,
//         "ajax": {
//             "url": "../../../../adw_biometric/userInsertion",
//             "type": "POST",
//             "data": function (d) {
//                 d.academic_year = $('#academic_year').val();
//                 d.district_id = $('#district_id').val();
               
//             },
//             "dataSrc": function (json) {
//                 return json;
//             }
//         },
//         "columns": [
//             { "data": null, "title": "S.No", "render": function (data, type, row, meta) { return meta.row + 1; } },
//             { "data": "hostel_taluk_1", "title": "Taluk Name" },
//             { "data": "cnt", "title": "Discontinued Count" }
//         ],
//         "dom": 'Bfrtip',
//         "searching": false,
//         "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
//         "pageLength": 10,
//         "order": [[1, 'asc']],
//         "responsive": true
//     });

//     $('#user_insertion_datatable tbody').on('click', 'tr td:nth-child(3)', function () {
//         var data = table.row($(this).closest('tr')).data();

//         if (data) {
//             loadModalData(data.hostel_taluk_1);
//             $('#Modal1').modal('show');
//         }
//     });
// });


function init_datatable(table_id = '', form_name = '', action = '') {
	var table = $("#" + table_id);
	var district_id = $('#district_id').val();
	var academic_year = $('#academic_year').val();
	
	var data = {
		"academic_year": academic_year,
		"district_id": district_id,
		"action": action,
	};
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({

		"ajax": {
			url: 'https://nallosaims.tn.gov.in/adw_biometric/usr_crud.php',
			type: "POST",
			data: data
		},
		dom: 'Blfrtip',
		searching: false,
		lengthChange: true,
		lengthMenu: [[10, 50, 100, 500, 1000], [10, 50, 100, 500, "Max"]],
		pageLength: 10,
		buttons: [{
			extend: 'copyHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'User Insertion'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'User Insertion',
			filename: 'user_insertion'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'User Insertion',
			filename: 'user_insertion'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'User Insertion',
			filename: 'user_insertion'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'User Insertion'
		}
		],
	});
}



// function user_insertion() {
// 	var std_reg_no = $('#std_reg_no').val();
// 	var std_name = $('#std_name').val().replace(/\s+/g, ''); // Remove all white spaces
// 	var ip = $('#ip').val();
// 	var d_user_name = $('#d_user_name').val();
// 	var d_password = $('#d_password').val();

// 	var ajax_url = sessionStorage.getItem("folder_crud_link");
// 	var data = {
// 		"std_reg_no": std_reg_no,
// 		"std_name": std_name,
// 		"ip": ip,
// 		"d_user_name": d_user_name,
// 		"d_password": d_password,
// 		"action": "user_insertion"
// 	};

// 	$.ajax({
// 		type: "POST",
// 		url: ajax_url,
// 		data: data,
// 		dataType: 'json',
// 		success: function (response) {
// 			if (response.status) {
// 				// Alert the RecNo and std_reg_no if insertion is successful
// 				log_alert("insertion", '', '', response.msg);
// 				//alert(response.msg);
// 			} else {
// 				console.error("Error processing records:", response.error);
// 				log_alert("insertion_alert", '', '', response.error);
// 				// alert("Error: " + response.error);
// 			}
// 		},
// 		error: function (xhr, status, error) {
// 			console.error("AJAX Error:", xhr.responseText);
// 			alert("An error occurred: " + xhr.responseText);
// 		}
// 	});
// }


function bio_register(hostel_id = '') {
	showLoader();
    
	var data = {
		"hostel_id": hostel_id,
		"action": "user_insert"
	};

	$.ajax({
		type: "POST",
		url: 'https://nallosaims.tn.gov.in/adw_biometric/usr_crud.php',
		data: data,
		dataType: 'json',
		success: function (response) {
			hideLoader();
			if (response.status) {
				var message = "Successfully registered: " + response.success_count + " students.\n" +
					"Failed to register: " + response.failure_count + " students.";

				// Display success message
				log_alert("insertion", '', '', message);
				init_datatable('user_insertion_datatable','','datatable');

				if (response.errors.length > 0) {
					console.error("Errors encountered during registration:", response.errors);
					//log_alert("insertion_alert", '', '', response.errors.join("\n"));
				}

			} else {
				console.error("Error processing records:", response.error);
				log_alert("insertion_alert", '', '', response.error);
			}
		},
		error: function (xhr, status, error) {
			hideLoader();
			console.error("AJAX Error:", xhr.responseText);
			alert("An error occurred: " + xhr.responseText);
		}
	});
}
	


function log_alert(msg = '', url = '', callback = '', title = '') {

	switch (msg) {

		case "insertion":
			Swal.fire({
				icon: 'success',
				title: title,
				imageAlt: 'Custom image',
				showConfirmButton: true,
				timer: 6000,
				timerProgressBar: true,
				willClose: () => {
					if (url) {
						window.location = url;
					}
				}
			});
			break;

		case "insertion_alert":
			Swal.fire({
				icon: 'warning',
				title: title,
				imageAlt: 'Custom image',
				showConfirmButton: true,
				timer: 6000,
				timerProgressBar: true,
				willClose: () => {
					if (url) {
						window.location = url;
					}
				}
			});
			break;

	}
}