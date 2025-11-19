$(document).ready(function () {
    app_download_datatable('app_download_datatable','app_download_datatable');
});

function print_app(unique_id = "") {


    var external_window = window.open('app_download_print.php?unique_id=' + unique_id, '_blank','height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
    external_window.print();
    
    }

function app_download_datatable(table_id = "", action = "") {
   

var unique_id = $("#unique_id").val();



var table = $("#" + table_id);
var data = {
    "unique_id": unique_id,
   
    "action": table_id,
};
var ajax_url = sessionStorage.getItem("folder_crud_link");
var datatable = new DataTable(table, {
    destroy: true,
    "searching": false,
    "paging": false,
    "ordering": false,
    "info": false,
    "ajax": {
        url: "app_crud.php",
        type: "POST",
        data: data
    },
    "columnDefs": [
			
			{ "targets": [4], "className": "dt-center" }
			
		]

});

}