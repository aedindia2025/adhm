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
var table_id 		= 'student_list_datatable';
var action 			= "datatable";

function init_datatable(table_id='', form_name='', action='') 
{
    var table = $("#"+table_id);
    var data = {
        "action" : action, 
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
	lengthChange: true,
	lengthMenu: [[10, 50, 100, 500, -1], [10, 50, 100, 500, "Max"]],
	pageLength: 10,
        buttons: [
            {
                extend: 'pdfHtml5',
                title: 'Registred Student List',
                filename: 'registred_student_list'
            }
        ]
    });
}
