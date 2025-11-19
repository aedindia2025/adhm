$(document).ready(function () {
    init_datatable(table_id, form_name, action);
});

var company_name = sessionStorage.getItem("company_name");
var company_address = sessionStorage.getItem("company_name");
var company_phone = sessionStorage.getItem("company_name");
var company_email = sessionStorage.getItem("company_name");
var company_logo = sessionStorage.getItem("company_name");

var form_name = 'Biometric Attendance Report';
var form_header = '';
var form_footer = '';
var table_name = '';
var table_id = 'staff_attendance_datatable';
var action = "datatable";

function filter() {
    init_datatable(table_id, form_name, action);
}

function init_datatable(table_id = '', form_name = '', action = '') {
    var table = $("#" + table_id);
    var from_date = $("#from_date").val();
    var to_date = $("#to_date").val();
    var district_name = $("#district_name").val();
    var taluk_name = $("#taluk_name").val();
    var hostel_name = $("#hostel_name").val();
    var establishment_type = $("#establishment_type").val();
    
    var data = {
        "from_date": from_date,
        "to_date": to_date,
        "district_name": district_name,
        "taluk_name": taluk_name,
        "hostel_name": hostel_name,
        "establishment_type": establishment_type,
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
        lengthChange: true,
        lengthMenu: [[10, 50, 100, 500, -1], [10, 50, 100, 500, "All"]],
        pageLength: 10,
        searching: false,

        buttons: [
            {
                extend: 'copyHtml5',
                title: 'Staff Attendance Report'
            },
            {
                extend: 'csvHtml5',
                title: 'Staff Attendance Report',
                filename: 'staff_attendance_report'
            },
            {
                extend: 'excelHtml5',
                title: 'Staff Attendance Report',
                filename: 'staff_attendance_report'
            },
            {
                extend: 'pdfHtml5',
                title: 'Staff Attendance Report',
                filename: 'staff_attendance_report'
            },
            {
                extend: 'print',
                title: 'Staff Attendance Report'
            }
        ],

        // Ensure the text in table cells wraps
        "createdRow": function (row, data, dataIndex) {
            $('td', row).css('white-space', 'normal');
        },

        // Enable fixed headers
        fixedHeader: true,
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

    // Get the values from the form
    var from_date = $("#from_date").val();
    var to_date = $("#to_date").val();
    var district_name = $("#district_name").val();
    var taluk_name = $("#taluk_name").val();
    var hostel_name = $("#hostel_name").val();
    var establishment_type = $("#establishment_type").val();

    // Use AJAX to request the file
    $.ajax({
        url: "folders/staff_attendance_report/excel.php",
        type: "GET",
        data: {
            from_date: from_date,
            to_date: to_date,
            district_name: district_name,
            taluk_name: taluk_name,
            hostel_name: hostel_name,
            establishment_type: establishment_type
        },
        xhrFields: {
            responseType: 'blob' // Important to get the file as a blob
        },
        success: function (data) {
            // Create a link element to trigger the download
            var link = document.createElement('a');
            var url = window.URL.createObjectURL(data);
            link.href = url;
            link.download = 'Staff_Attendance_Report.xls'; // Set the filename
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

function showLoader() {

	$("#loader").css("display", "inline-block"); // or "block" depending on your preference
}

function hideLoader() {
	$("#loader").css("display", "none");
}


$("#consolidated_export").click(function () {
    // Show the loader
    showLoader();
    $("#consolidated_export").prop("disabled", true);

    // Get the values from the form
    // Get the values from the form
    var from_date = $("#from_date").val();
    var to_date = $("#to_date").val();
    var district_name = $("#district_name").val();
    var taluk_name = $("#taluk_name").val();
    var hostel_name = $("#hostel_name").val();
    var establishment_type = $("#establishment_type").val();

    // Use AJAX to request the file
    $.ajax({
        url: "folders/staff_attendance_report/consolidated_excel.php",
        type: "GET",
        data: {
            from_date: from_date,
            to_date: to_date,
            district_name: district_name,
            taluk_name: taluk_name,
            hostel_name: hostel_name,
            establishment_type: establishment_type,
        },
        xhrFields: {
            responseType: 'blob' // Important to get the file as a blob
        },
        success: function (data) {
            // Create a link element to trigger the download
            var link = document.createElement('a');
            var url = window.URL.createObjectURL(data);
            link.href = url;
            link.download = 'Staff_Attendance_Report.xls'; // Set the filename
            document.body.appendChild(link);
            link.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(link);

            // Hide the loader
            hideLoader();
            $("#consolidated_export").prop("disabled", false);

        },
        error: function () {
            // Hide the loader if there's an error
            hideLoader();
            alert("An error occurred while generating the report.");
            $("#consolidated_export").prop("disabled", false);

        }
    });
});