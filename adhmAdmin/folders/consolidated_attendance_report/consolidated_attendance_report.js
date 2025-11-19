$(document).ready(function () {
	// var table_id 	= "user_type_datatable";
	init_datatable(table_id,form_name,action);
get_hostel_details();
get_att_details();

 });
var company_name 	= sessionStorage.getItem("company_name");
var company_address	= sessionStorage.getItem("company_name");
var company_phone 	= sessionStorage.getItem("company_name");
var company_email 	= sessionStorage.getItem("company_name");
var company_logo 	= sessionStorage.getItem("company_name");

var form_name 		= 'Consolidated Report';
var form_header		= '';
var form_footer 	= '';
var table_name 		= '';
var table_id 		= 'consolidated_report_datatable';
var action 			= "datatable";

function filter(){

init_datatable(table_id,form_name,action);

}
function generateTableHeaders(monthYear) {
    let parts = monthYear.split('-'); 
    let year = parseInt(parts[0], 10);
    let month = parseInt(parts[1], 10);
    let daysInMonth = new Date(year, month, 0).getDate();

    $("#tot_days").val(daysInMonth);

    // Month Names Array
    let monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    let monthName = monthNames[month - 1];

    // ** Main Table UI Headers (With Rowspan & Colspan) **
    let headerRow1 = `<tr>
        <th rowspan="2" style="text-align:center;">S No</th>
        <th rowspan="2" style="text-align:center;">District</th>
        <th rowspan="2" style="text-align:center;">Taluk</th>
        <th rowspan="2" style="text-align:center;">Hostel ID / Hostel Name</th>
        <th rowspan="2" style="text-align:center;">DADWO Approved</th>
        <th rowspan="2" style="text-align:center;">Biometric Registered</th>
        <th rowspan="2" style="text-align:center;">Face Enrolled</th>
        <th rowspan="2" style="text-align:center;">Fingerprint Enrolled</th>`;

    for (let day = 1; day <= daysInMonth; day++) {
        headerRow1 += `<th colspan="2" style="text-align:center;">${monthName} ${day}</th>`;
    }
    headerRow1 += `<th colspan="2" style="text-align:center;">Total</th></tr>`;

    let headerRow2 = `<tr>`;
    for (let day = 1; day <= daysInMonth; day++) {
        headerRow2 += `<th style="text-align:center;">MRG</th>
                       <th style="text-align:center;">EVE</th>`;
    }
    headerRow2 += `<th style="text-align:center;">MRG</th>
                   <th style="text-align:center;">EVE</th></tr>`;

    // ** Excel Export Header (No Rowspan/Colspan) - Hidden **
    let exportHeaderRow = `<tr class="export-header" style="display: none;">
        <th>S No</th>
        <th>District</th>
        <th>Taluk</th>
        <th>Hostel ID / Hostel Name</th>
        <th>DADWO Approved</th>
        <th>Biometric Registered</th>
        <th>Face Enrolled</th>
        <th>Fingerprint Enrolled</th>`;
 
    for (let day = 1; day <= daysInMonth; day++) {
        exportHeaderRow += `<th>${monthName} ${day} MRG</th>
                            <th>${monthName} ${day} EVE</th>`;
    }
    exportHeaderRow += `<th>Total MRG</th><th>Total EVE</th></tr>`;

    // Apply Headers to Table
    $("#consolidated_report_datatable thead").html(headerRow1 + headerRow2 + exportHeaderRow);
}

function init_datatable(table_id = '', form_name = '', action = '') {

if ($.fn.DataTable.isDataTable("#consolidated_report_datatable")) {
        $("#consolidated_report_datatable").DataTable().clear().destroy();
    }
     var table = $("#" + table_id);
    var month_year = $("#month_year").val();
    var district_name = $("#district_name").val();
    var taluk_name = $("#taluk_name").val();
    generateTableHeaders(month_year);
    var data = {
        "action": action,
        "month_year": month_year,
        "district_name": district_name,
        "taluk_name": taluk_name,
    };    var ajax_url = sessionStorage.getItem("folder_crud_link");



    var datatable = table.DataTable({
        "ajax": {
            url: ajax_url,
            type: "POST",
            data: data
        },
        "dom": 'Blfrtip',
        "lengthChange": true,
        "lengthMenu": [[10, 50, 100, 500, -1], [10, 50, 100, 500, "All"]],
        "pageLength": 10,
        "searching": false,
        "responsive": false,
        "fixedHeader": true,
        "buttons": [
            {
                extend: 'excelHtml5',
                text: 'Export to Excel',
                customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    var monthYear = $("#month_year").val();
                    var parts = monthYear.split('-');
                    var year = parseInt(parts[0], 10);
                    var month = parseInt(parts[1], 10);
                    var daysInMonth = new Date(year, month, 0).getDate();

                    var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                    var monthName = monthNames[month - 1];

                    // Modify Excel header row correctly
                    $(sheet).find('row:first c').each(function(index) {
                        if (index === 0) {
                            $(this).find("v").text('Hostel ID');
                        } else {
                            let day = Math.ceil(index / 2);
                            let session = index % 2 === 1 ? "MRG" : "EVE";
                            $(this).find("v").text(`${monthName} ${day} ${session}`);
                        }
                        $(this).attr('s', '2'); // Apply bold style
                    });
 $(sheet).find('row c').attr('s', '51');
                },
                exportOptions: {
                    columns: ':visible',
                    modifier: {
                        page: 'all'
                    },
                    header: true
                },
                title: 'Consolidated Report',
                filename: 'consolidated_attendance_report'
            }
        ],
        "columnDefs": [
            {
                "targets": [-2, -1], // Last two columns (Total MRG, Total EVE)
                "className": "bold-text"
            }
		
        ],
        "createdRow": function (row, data, dataIndex) {
            if (data[0] === "Total") {
                $(row).addClass('bold-text'); // Apply bold style
            }
        }
    });

    // Hide extra export header row
    $(".export-header").hide();
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

function get_hostel_details() {

    var district_name = $('#district_name').val();
    var taluk_name = $('#taluk_name').val();
    var month_year = $('#month_year').val();



    var data = "district_name=" + district_name + "&action=get_hostel_details&taluk_name="+taluk_name;

    var ajax_url = sessionStorage.getItem("folder_crud_link");

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            
            var obj = JSON.parse(data);
           var tot_hostel = obj.tot_hostel;
           var bio_reg_cnt = obj.bio_reg_cnt;

                $("#tot_hostel").val(tot_hostel);
                $("#bio_reg_cnt").val(bio_reg_cnt);
        }
    });

}
function get_att_details() {

    var district_name = $('#district_name').val();
    var taluk_name = $('#taluk_name').val();
    var month_year = $('#month_year').val();



    var data = "district_name=" + district_name + "&action=get_att_details&taluk_name="+taluk_name+'&month_year='+month_year;

    var ajax_url = sessionStorage.getItem("folder_crud_link");

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            
            var obj = JSON.parse(data);
           var mrg_cnt = obj.mrg_cnt;
           var eve_cnt = obj.eve_cnt;
           var tot_punch_cnt = obj.tot_punch_cnt;

                $("#mrg_cnt").val(mrg_cnt);
                $("#eve_cnt").val(eve_cnt);
                $("#tot_punch_cnt").val(tot_punch_cnt);
        }
    });

}



function openPrintWindow(hostel_id,date_val,type,count) {
       
   var external_window = window.open('folders/consolidated_attendance_report/view_app.php?hostel='+hostel_id+'&date='+date_val+'&type='+type+'&count='+count, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');

// external_window.print();
}