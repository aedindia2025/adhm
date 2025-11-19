$(document).ready(function () {
	// var table_id 	= "user_type_datatable";
	init_datatable(table_id, form_name, action);
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
var table_id = 'application_report_datatable';
var action = "datatable";
function filter() {
	init_datatable(table_id, form_name, action);
}

function init_datatable(table_id = '', form_name = '', action = '') {
	var table = $("#" + table_id);
	var district_name = $("#district_name").val();
	var amc_name = $("#amc_name").val();
	var taluk_name = $("#taluk_name").val();
	var hostel_name = $("#hostel_name").val();
	var app_status = $("#app_status").val();
	var data = {
		"district_name": district_name,
		"academic_year": amc_name,
		"taluk_name": taluk_name,
		"hostel_name": hostel_name,
		"app_status": app_status,
		"action": action,
	};
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({

		"ajax": {
			url: ajax_url,
			type: "POST",
			data: data
		},
		dom: 'Blfrtip',
		searching: false,
		responsive: false,
		lengthChange: true,
		lengthMenu: [[10, 1000, 10000, 100000], [10, 1000, 10000, 100000]],
		pageLength: 10,
		buttons: [{
			extend: 'copyHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Student Application Report'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Student Application Report',
			filename: 'student_applications'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Student Application Report',
			filename: 'student_applications'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Student Application Report',
			filename: 'student_applications',
			orientation: 'landscape', // Change to landscape
			pageSize: 'A4', // Set page size
			customize: function (doc) {
				doc.styles.tableHeader.alignment = 'left'; // Adjust alignment
				doc.defaultStyle.fontSize = 8; // Change font size
				doc.styles.tableHeader.fontSize = 10; // Change header font size
				doc.pageMargins = [4, 10, 4, 10];  // change margin
			}
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Student Application Report'
		}
		]
	});
}

function get_taluk() {


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

function get_hostel() {



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

function view_app(unique_id = "") {


	var external_window = window.open('folders/application_report/view_app.php?unique_id=' + unique_id, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
	// external_window.print();
	// external_window.print();
}

function print_view() {
	onmouseover = window.open('adhm/adhmAdmin/uploads/document_upload', 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
}

function print_pdf(file_name) {

	onmouseover = window.open('../adhmAdmin/uploads/carrier_guidance/documents' + file_name, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
}



// $("#export").click(function () {
// 	window.location = "folders/application_report/excel.php";
// });

function showLoader() {
	$("#loader").css("display", "inline-block"); // or "block" depending on your preference
}

function hideLoader() {
	$("#loader").css("display", "none");
}


$("#export").click(function () {
    // Show the loader
    showLoader();
	$("#export").prop("disabled", true);

	var amc_name = $("#amc_name").val();
 var data = {
	"academic_year" : amc_name
 }

    // Use AJAX to request the file
    $.ajax({
        url: "folders/application_report/excel.php",
         type: "GET",
		data: data,
        xhrFields: {
            responseType: 'blob' // Important to get the file as a blob
        },
        success: function (data) {
            // Create a link element to trigger the download
            var link = document.createElement('a');
            var url = window.URL.createObjectURL(data);
            link.href = url; 
            link.download = 'Student_Application_Report.xls'; // Set the filename
            document.body.appendChild(link);
            link.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(link);

            // Hide the loader
            hideLoader();
			$("#export").prop("disabled", false);

        },
        error: function () {
            // Hide the loader if there's an error
            hideLoader();
            alert("An error occurred while generating the report.");
			$("#export").prop("disabled", false);

        }
    });
});
