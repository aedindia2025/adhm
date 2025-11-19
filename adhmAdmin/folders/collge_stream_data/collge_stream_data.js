$(document).ready(function () {
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
var table_id = 'college_stream_data_datatable';
var action = "datatable";

function init_datatable(table_id = '', form_name = '', action = '') {
	var table = $("#" + table_id);
	var district_name = $('#district_name').val();
	var taluk_name = $('#taluk_name').val();
	var hostel_name = $('#hostel_name').val();
	var data = {
		"district_name": district_name,
		"taluk_name": taluk_name,
		"hostel_name": hostel_name,
		"action": action,
	};
	// var ajax_url = "https://nallosaims.tn.gov.in/adw_biometric/folders/registered_to_device/crud.php";
	var ajax_url = "folders/collge_stream_data/crud.php";

	var datatable = table.DataTable({
		"ajax": {
			url: ajax_url,
			type: "POST",
			data: data
		},
		dom: 'Blfrtip',
		searching: false,
		lengthChange: true,
		lengthMenu: [[10, 50, 100, 500, 1000], [10, 50, 100, 500, "Max"]],
		pageLength: 10,
		buttons: [
			{
				extend: 'copyHtml5',
				exportOptions: {
					columns: ':visible'
				},
				title: 'college stream & standard wise data'
			},
			{
				extend: 'csvHtml5',
				exportOptions: {
					columns: ':visible'
				},
				title: 'college stream & standard wise data',
				filename: 'college_standard_wise_data'
			},
			{
				extend: 'excelHtml5',
				exportOptions: {
					columns: ':visible'
				},
				title: 'college stream & standard wise data',
				filename: 'college_standard_wise_data'
			},
			{
				extend: 'pdfHtml5',
				exportOptions: {
					columns: ':visible'
				},
				title: 'college stream & standard wise data',
				filename: 'college_standard_wise_data'
			},
			{
				extend: 'print',
				exportOptions: {
					columns: ':visible'
				},
				title: 'Biometric Not Captured Students'
			}
		],
		columnDefs: [
			{ width: '5%', targets: 0 },   // S.No
			{ width: '15%', targets: 1 },  // District Name
			{ width: '15%', targets: 2 },  // Taluk Name
			{ width: '20%', targets: 3 },  // Hostel Name
			{ width: '20%', targets: 4 },
			{ width: '20%', targets: 5 },
			// { width: '20%', targets: 6 },
		],
		"createdRow": function (row, data, dataIndex) {
			$('td', row).css('white-space', 'normal');
		}

	});
}

function taluk() {

	var district_name = $('#district_name').val();


	var data = "district_name=" + district_name + "&action=district_name";
	// var ajax_url = "https://nallosaims.tn.gov.in/adw_biometric/folders/registered_to_device/crud.php";
	var ajax_url = "folders/collge_stream_data/crud.php";
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

	// var ajax_url = "https://nallosaims.tn.gov.in/adw_biometric/folders/registered_to_device/crud.php";
		var ajax_url = "folders/collge_stream_data/crud.php";


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

function go_filter() {

	var district_name = $('#district_name').val();

	var taluk_name = $('#taluk_name').val();
	var hostel_name = $('#hostel_name').val();



	// var ajax_url = "https://nallosaims.tn.gov.in/adw_biometric/folders/registered_to_device/crud.php";
		var ajax_url = "folders/collge_stream_data/crud.php";

	var data = {
		"district_name": district_name,
		"taluk_name": taluk_name,
		"hostel_name": hostel_name,
		"action": 'datatable',

	};

	init_datatable(table_id, form_name, action, data);

}



function showLoader() {
	$("#loader").css("display", "inline-block"); 
}

function hideLoader() {
	$("#loader").css("display", "none");
}


$("#export").click(function () {
    // Show the loader
    showLoader();
	$("#export").prop("disabled", true);

    // Use AJAX to request the file
    $.ajax({
        url: "folders/registered_to_device/excel.php",
        type: "GET",
        xhrFields: {
            responseType: 'blob' // Important to get the file as a blob
        },
        success: function (data) {
            // Create a link element to trigger the download
            var link = document.createElement('a');
            var url = window.URL.createObjectURL(data);
            link.href = url;
            link.download = 'Registered_Student_list.xls'; // Set the filename
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